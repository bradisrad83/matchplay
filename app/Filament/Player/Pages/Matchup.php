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

    public function mount()
    {
        $this->scorecard = auth()->user()->getCurrentScorecard();
        $this->meta = $this->scorecard->getScorecardMeta();
        $this->teamOne = $this->scorecard->teamOne();
        $this->teamTwo = $this->scorecard->teamTwo();

        $holeData = is_array($this->scorecard->hole_data)
            ? $this->scorecard->hole_data
            : (array) $this->scorecard->hole_data;

        $this->currentHole = $this->getStartingHole($holeData);
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
            $this->currentHole--;
        }
        $this->clampCurrentHole();
    }

    public function nextHole(): void
    {
        if ($this->currentHole < $this->maxHole) {
            $this->currentHole++;
        }
        $this->clampCurrentHole();
    }

    private function clampCurrentHole(): void
    {
        $this->currentHole = max(1, min($this->currentHole, $this->maxHole));
    }

    public function getHoleScore(string $slug): ?int
    {
        $rows = (array) ($this->scorecard?->hole_data ?? []);
        $i = max(0, $this->currentHole - 1);

        if (isset($rows[$i]) && (int) ($rows[$i]['hole_number'] ?? 0) === (int) $this->currentHole) {
            $this->teamScore = [$slug => (int) $rows[$i][$slug] ?? 0];
            return (int) ($rows[$i][$slug] ?? 0);
        }
        foreach ($rows as $row) {
            if ((int) ($row['hole_number'] ?? 0) === (int) $this->currentHole) {
                $this->teamScore = [$slug => (int) $rows[$slug] ?? 0];
                return (int) ($row[$slug] ?? 0);
            }
        }

        return 0;
    }
}
