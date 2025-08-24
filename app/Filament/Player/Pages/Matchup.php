<?php

namespace App\Filament\Player\Pages;

use App\Models\Scorecard;
use App\Models\Team;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class Matchup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.player.pages.matchup';

    public ?Scorecard $scorecard = null;

    public int $currentHole;

    public int $maxHole = 18;

    public ?Team $teamOne = null;

    public ?Team $teamTwo = null;

    public ?Collection $meta = null;

    public ?Collection $totalScore = null;

    public array $teamScore = [];

    public array $holeData = [];

    public array $currentScore = [];

    public bool $showFinalize = false;

    public function mount()
    {
        $this->scorecard = auth()->user()->getCurrentScorecard();
        $this->meta = $this->scorecard->getScorecardMeta();
        $this->teamOne = $this->scorecard->teamOne();
        $this->teamTwo = $this->scorecard->teamTwo();

        $this->holeData = is_array($this->scorecard->hole_data)
            ? $this->scorecard->hole_data
            : (array) $this->scorecard->hole_data;

        $this->currentHole = $this->getStartingHole($this->holeData);
        $this->currentScore = $this->scorecard->getCurrentScore();
        $this->showFinalize = $this->scorecard->finalized;
    }

    public function getViewData(): array
    {
        return [
        ];
    }

    /**
     * Decide which hole to open when the page loads.
     *
     * Priority:
     *  1) First hole where exactly one team is null (in-progress)
     *  2) First hole where both teams are null (not started)
     *  3) Last hole number (all filled)
     */
    protected function getStartingHole(array $hole_data): int
    {
        if (empty($hole_data)) {
            return 1;
        }

        // Prefer explicit team slugs; fall back to inferring from the first item keys.
        $team1Key = $this->teamOne?->slug ?? null;
        $team2Key = $this->teamTwo?->slug ?? null;

        if (! $team1Key || ! $team2Key) {
            $first = $hole_data[0] ?? [];
            $teamKeys = array_values(array_diff(array_keys($first), ['hole_number', 'label', 'winner']));
            $team1Key = $team1Key ?: ($teamKeys[0] ?? null);
            $team2Key = $team2Key ?: ($teamKeys[1] ?? null);
        }

        $firstIncomplete = null; // exactly one side null
        $firstBothNull = null; // both sides null

        foreach ($hole_data as $hole) {
            $num = (int) ($hole['hole_number'] ?? 1);
            $t1 = $team1Key !== null ? ($hole[$team1Key] ?? null) : null;
            $t2 = $team2Key !== null ? ($hole[$team2Key] ?? null) : null;

            // Track the first partially-entered hole (resume here first).
            if ((is_null($t1) xor is_null($t2)) && $firstIncomplete === null) {
                $firstIncomplete = $num;
            }

            // Track the first untouched hole as the secondary option.
            if (is_null($t1) && is_null($t2) && $firstBothNull === null) {
                $firstBothNull = $num;
            }
        }

        if ($firstIncomplete !== null) {
            return $firstIncomplete;
        }

        if ($firstBothNull !== null) {
            return $firstBothNull;
        }

        // Everything filled — send them to the last hole.
        $last = (int) ($hole_data[array_key_last($hole_data)]['hole_number'] ?? 18);

        return $last > 0 ? $last : 1;
    }

    public function previousHole(): void
    {
        if ($this->currentHole > 1) {
            $this->checkHoleWinner();
            $this->saveScorecard();
            $this->currentHole--;
        }
        $this->clampCurrentHole();
    }

    public function nextHole(): void
    {
        // Always try to decide/save the current hole before moving
        $this->checkHoleWinner();
        $this->saveScorecard();

        if ($this->currentHole < $this->maxHole) {
            // Normal navigation up to the final hole
            $this->currentHole++;
            $this->showFinalize = false; // hide finalize until we hit 18
        } else {
            // We're on the last hole: don't advance, just show finalize
            $this->showFinalize = true;
        }

        $this->clampCurrentHole();
    }

    private function clampCurrentHole(): void
    {
        $this->currentHole = max(1, min($this->currentHole, $this->maxHole));
    }

    public function getHoleScore(string $slug): int
    {
        foreach ((array) $this->holeData as $row) {
            if ((int) ($row['hole_number'] ?? 0) === (int) $this->currentHole) {
                $val = $row[$slug] ?? null;

                return is_null($val) ? 0 : (int) $val;
            }
        }

        return 0; // no row yet for this hole -> show baseline
    }

    private function findHoleIndex(int $hole): ?int
    {
        foreach ($this->holeData as $idx => $row) {
            if ((int) ($row['hole_number'] ?? 0) === $hole) {
                return $idx;
            }
        }

        return null;
    }

    private function ensureHoleRow(int $hole): int
    {
        $i = $this->findHoleIndex($hole);
        if ($i !== null) {
            return $i;
        }

        $this->holeData[] = [
            'hole_number' => $hole,
            'label' => "Hole {$hole}",
            // leave team slugs unset until first adjust
        ];

        // reindex to keep numeric keys tight (helps Livewire diffs)
        $this->holeData = array_values($this->holeData);

        return array_key_last($this->holeData);
    }

    private function clamp(int $n, int $min = 1, int $max = 10): int
    {
        return max($min, min($max, $n));
    }

    public function adjustHoleScore(string $slug, int $delta): void
    {
        $delta = $delta < 0 ? -1 : 1;             // normalize
        $i = $this->ensureHoleRow($this->currentHole);

        $current = (int) ($this->holeData[$i][$slug] ?? 0);
        $new = $this->clamp($current + $delta, 1, 10);

        $this->holeData[$i][$slug] = $new;
    }

    public function incrementScore(string $slug): void
    {
        $this->adjustHoleScore($slug, 1);
    }

    public function decrementScore(string $slug): void
    {
        $this->adjustHoleScore($slug, -1);
    }

    public function checkHoleWinner(): void
    {
        $i = max(0, $this->currentHole - 1);

        // No row yet? Nothing to decide.
        if (! isset($this->holeData[$i]) || (int) ($this->holeData[$i]['hole_number'] ?? 0) !== $this->currentHole) {
            return;
        }

        $row = &$this->holeData[$i];

        // Slugs to read scores
        $slug1 = data_get($this->teamOne, 'slug');
        $slug2 = data_get($this->teamTwo, 'slug');

        // Fallback to meta if needed
        if (! $slug1 || ! $slug2) {
            $slugs = collect($this->meta)->pluck('slug')->values();
            $slug1 = $slug1 ?: (string) $slugs->get(0);
            $slug2 = $slug2 ?: (string) $slugs->get(1);
        }

        // If either side is unset/null, don't decide yet
        $has1 = array_key_exists($slug1, $row) && $row[$slug1] !== null && $row[$slug1] !== '';
        $has2 = array_key_exists($slug2, $row) && $row[$slug2] !== null && $row[$slug2] !== '';

        if (! $has1 || ! $has2) {
            $row['winner'] = null;

            return;
        }

        $s1 = (int) $row[$slug1];
        $s2 = (int) $row[$slug2];

        if ($s1 === $s2) {
            $row['winner'] = 'push';
        } elseif ($s1 < $s2) {
            // Lower score wins in match play
            $row['winner'] = data_get($this->teamOne, 'id');
        } else {
            $row['winner'] = data_get($this->teamTwo, 'id');
        }
    }

    private function saveScorecard(): void
    {
        $this->scorecard->hole_data = $this->holeData;
        $this->scorecard->save();
        $this->currentScore = $this->scorecard->getCurrentScore();
    }

    public function finalizeMatch(): void
    {
        // Safety: ensure the last hole has a winner
        $this->checkHoleWinner();
        $this->saveScorecard();

        $status = $this->scorecard->getCurrentScore(); 
        $this->scorecard->team_id = $status['team_id'] ?? null;
        $this->scorecard->finalized = true; 
        $this->scorecard->save();
    }
}
