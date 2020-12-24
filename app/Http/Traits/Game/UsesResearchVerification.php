<?php

namespace App\Http\Traits\Game;

use App\Models\Player;

trait UsesResearchVerification
{

    /**
     * @function verify research priority is within bounds
     * @param float $priority
     * @return bool
     */
    private function isResearchPriorityValid (float $priority): bool
    {
        $rules = config('rules.tech.researchPriority');
        if (!is_float($priority)) return false;
        if ($priority < $rules['min'] || $priority > $rules['max']) return false;
        return true;
    }

    /**
     * @function verify the number of research jobs is not > max
     * @param array $jobIds
     * @return bool
     */
    private function verifyResearchQueueMax (array $jobIds): bool
    {
        return count($jobIds) <= config('rules.tech.queue');
    }

    /**
     * @function verify all research jobs exist and are owned by the player
     * @param array $jobIds
     * @param Player $player
     * @return bool
     */
    private function researchJobsArePlayerOwned (array $jobIds, Player $player): bool
    {
        $jobs = $player->researches;
        foreach($jobIds as $jobId) {
            if (!$jobs->containsStrict('id', $jobId)) return false;
        }
        return true;
    }

    /**
     * @function verify the research jobs are in the expected order.
     * @param array $jobIds
     * @param Player $player
     * @return bool
     */
    private function isSortOrderCorrect (array $jobIds, Player $player): bool
    {
        $tls = $player->techLevels;
        $jobs = $player->researches;
        // prepare array with all areas and the expected levels.
        $expectedLevels = array_flip(array_keys(config('rules.tech.areas')));
        foreach($expectedLevels as $key => $value) {
            $expectedLevels[$key] = $tls->where('type', $key)->first()->level + 1;
        }
        // loop research jobs in the order indicated by the client
        foreach($jobIds as $jobId) {
            $job = $jobs->find($jobId);
            // is the level unexpected?
            if ($expectedLevels[$job->type] !== $job->level) {
                return false;
            } else { // if the level is expected, increase by 1 for next check.
                $expectedLevels[$job->type] = $job->level + 1;
            }
        }
        return true;
    }

    /**
     * @function ensure the area exists
     * @param string $type
     * @return bool
     */
    private function researchAreaExists (string $type): bool
    {
        return array_key_exists($type, config('rules.tech.areas'));
    }

    /**
     * @function ensure the level is within bounds
     * @param int $level
     * @return bool
     */
    private function isLevelWithinBounds (int $level): bool
    {
        if (!is_numeric($level)) return false;
        return $level >= config('rules.tech.bounds.min')
            && $level < config('rules.tech.bounds.max');
    }

    /**
     * @function verify the new level is not already researched or enqueued
     * @param Player $player
     * @param string $type
     * @param int $level
     * @return bool
     */
    private function isJobResearchable (Player $player, string $type, int $level): bool
    {
        $currentLevel = $player->techLevels->where('type', $type)->first()->level;
        $researches = $player->researches->where('type', $type)
            ->sortByDesc('level')->first();
        $nextLevel = $currentLevel + 1;
        if ($researches) $nextLevel = $researches->level + 1;
        if ($nextLevel !== $level) return false;
        return true;
    }

}
