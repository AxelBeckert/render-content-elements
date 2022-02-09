<?php

namespace Ccm\CcmEventHandling\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;

class AbstractRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * initializeObject
     */
    public function initializeObject()
    {
        /** @var QuerySettingsInterface $querySettings */
        $querySettings = $this->objectManager->get(QuerySettingsInterface::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @return void
     */
    public function persistAll()
    {
        $this->persistenceManager->persistAll();
    }

    /**
     * @return void
     */
    public function clearState()
    {
        $this->persistenceManager->clearState();
    }

}
