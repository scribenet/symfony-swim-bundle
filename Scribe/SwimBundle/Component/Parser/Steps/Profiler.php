<?php
/*
 * This file is part of the Scribe World Application.
 *
 * (c) Scribe Inc. <scribe@scribenet.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Scribe\LearningBundle\Utility\Parser\Swim;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;
use Scribe\LearningBundle\Utility\Parser\ParserInterface;

/**
 * SwimParserProfiler
 */
class SwimParserProfiler extends SwimParserObserver implements ParserInterface, ContainerAwareInterface
{
    /**
     * @var boolean
     */
    protected $firstPass = true;

    /**
     * @var float
     */
    protected $startTime = null;

    /**
     * @var float
     */
    protected $endTime = null;

    /**
     * @param null $string
     * @return mixed|null
     */
    public function render($string = null)
    {
        if ($this->firstPass === true) {
            $this->firstPass = false;
            return $this->firstPass($string);
        } else {
            return $this->secondPass($string);
        }
    }

    /**
     * @param string $string
     * @return string
     */
    public function firstPass($string = null)
    {
        $this->startTime = microtime(true);

        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    public function secondPass($string = null)
    {
        $this->stopTime = microtime(true);
        
        $totalTime = $this->stopTime - $this->startTime;

        $string = str_ireplace('<p>{~swim:profiler:time}</p>', '<!-- render-time: '.round($totalTime, 10).' seconds -->', $string);
        $string = str_ireplace('{~swim:profiler:time}', '<!-- render-time: '.round($totalTime, 10).' seconds -->', $string);

        return $string;
    }
}