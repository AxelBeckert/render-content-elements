<?php

declare(strict_types=1);

namespace Ccm\CcmEventHandling\Controller;

use Ccm\CcmEventHandling\Domain\Model\Content;
use Ccm\CcmEventHandling\Domain\Repository\ContentRepository;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\RecordsContentObject;

/**
 * This file is part of the "Event Verwaltung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Axel Beckert <a.beckert@ccmagnus.de>, ccmagnus OHG
 *
 */

/**
 * EventController
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * eventRepository
     *
     * @var \Ccm\CcmEventHandling\Domain\Repository\EventRepository
     */
    protected $eventRepository = null;

    /**
     * @var ContentRepository
     */
    protected $contentRepository = null;

    /**
     * @param \Ccm\CcmEventHandling\Domain\Repository\EventRepository $eventRepository
     */
    public function injectEventRepository(\Ccm\CcmEventHandling\Domain\Repository\EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param ContentRepository $contentRepository
     * @return void
     */
    public function injectContentRepository(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * action list
     *
     * @throws \TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException
     * @return string|object|null|void
     */
    public function listAction()
    {
        $content = $this->renderContentOfPage(1);
        $events = $this->eventRepository->findAll();
        $this->view->assign('events', $events);
        $this->view->assign('content', $content);
    }

    /**
     * action show
     *
     * @param \Ccm\CcmEventHandling\Domain\Model\Event $event
     * @return string|object|null|void
     */
    public function showAction(\Ccm\CcmEventHandling\Domain\Model\Event $event)
    {
        $this->view->assign('event', $event);
    }

    /**
     * @param int $pid
     * @return string
     */
    protected function renderContentOfPage(int $pid)
    {

        /** @var QueryResultInterface $contentObjects */
        $contentObjects = $this->contentRepository->findByPid(1);
        return $this->renderContentObjects($contentObjects);
    }

    /**
     * @param \Iterator $contentObjects
     */
    protected function renderContentObjects(\Iterator $contentObjects)
    {
        $recordContentObjectRenderer = $this->buildRecordContentRenderer();
        $content = '';

        /** @var Content $contentObject */
        foreach ($contentObjects as $contentObject) {
            $conf = array(
            'tables' => 'tt_content',
            'source' => $contentObject->getUid(),
            'dontCheckPid' => 1
            );
            $content .= $recordContentObjectRenderer->render($conf);
        }
        return $content;
    }

    /**
     * @return RecordsContentObject
     */
    protected function buildRecordContentRenderer()
    {

        /** @var ContentObjectRenderer $cObj */
        $cObj = $this->configurationManager->getContentObject();
        return GeneralUtility::makeInstance(RecordsContentObject::class, $cObj);
    }
}
