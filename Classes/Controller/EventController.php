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
 * EventController
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var ContentRepository
     */
    protected $contentRepository = null;

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
        $content = $this->renderContentOfPageWith(1);
        $this->view->assign('content', $content);
    }

    /**
     * @param int $pid
     * @return string
     */
    protected function renderContentOfPageWith(int $pid)
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
