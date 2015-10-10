<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 10.10.15
 * Time: 9:31
 */

namespace Framework\Localization;


use Framework\Session\SessionManager;

class LocalizationManager
{

    private $languageSettings;

    /**
     * @param $languageSettings array with localization settings from config files
     */
    public function __construct($languageSettings) {
        $this->languageSettings = $languageSettings;
    }

    /**
     * method use for setting locale and aplying in next request
     * @param $locale set localization in sessionContext
     */
    public function setLocale($locale) {
        $availableLanguages = $this->languageSettings['available'];
        if (array_key_exists($locale, $availableLanguages)) {
            $locale_code = $locale;
        } else {
            $default = $this->languageSettings['default'];
            $locale_code = $default;
        }
        $session = new SessionManager();
        $session->putParameter('locale', $locale_code);
    }

    /**
     * apply localization settings
     */
    public function applyLocale() {
        $localeShortcut = $this->getCurrentLocale();
        $available = $this->languageSettings['available'];
        $locale = $available[$localeShortcut];

        putenv("LANG=".$locale);

        setlocale (LC_ALL, $locale);

        $domain = 'messages';
        $path = __DIR__.'/../../locale';
        bindtextdomain ($domain, $path);
        textdomain($domain);
    }

    /**
     * @return shortcut name of locale
     */
    public function getCurrentLocale() {
        $session = new SessionManager();
        $locale = $session->getParameter('locale');
        if ($locale == null) {
            $locale = $this->languageSettings['default'];
        }
        return $locale;
    }

    /**
     * @return mixed array with all available locales
     */
    public function getAvailableLocales() {
        return $this->languageSettings['available'];
    }
}