<?php

namespace T3Monitor\T3monitoringClient\Provider;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extensionmanager\Utility\EmConfUtility;
use TYPO3\CMS\Extensionmanager\Utility\ListUtility;

class Extension6xProvider implements DataProviderInterface
{

    public function get(array $data)
    {
        $GLOBALS['LANG'] = GeneralUtility::makeInstance('TYPO3\\CMS\\Lang\\LanguageService');
        $GLOBALS['LANG']->init('default');

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        /** @var ListUtility $listUtility */
        $listUtility = $objectManager->get('TYPO3\\CMS\\Extensionmanager\\Utility\\ListUtility');

        $allExtensions = $listUtility->getAvailableExtensions();

        /** @var EmConfUtility $emConfUtility */
        $emConfUtility = GeneralUtility::makeInstance('TYPO3\\CMS\\Extensionmanager\\Utility\\EmConfUtility');
        foreach ($allExtensions as $key => $f) {
            if (is_dir(PATH_site . 'typo3/sysext/' . $key . '/')) {
                continue;
            }
            $data['extensions'][$key] = $emConfUtility->includeEmConf($f);
            $data['extensions'][$key]['isLoaded'] = (int)ExtensionManagementUtility::isLoaded($key);
        }

        return $data;
    }

}