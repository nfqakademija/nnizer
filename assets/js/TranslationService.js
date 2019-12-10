import Translator from 'bazinga-translator';
import trans_en from './../js/translations/en.json';
import trans_lt from './../js/translations/lt.json';

const getTranslation = (key, domain = "messages") => {
    const currentLocale = Translator.locale;
    const translations = getTranslationsByLocale(currentLocale);

    return translations.translations[currentLocale][domain][key];
};

const getTranslationsByLocale = (locale) => {
    switch (locale) {
        case "en":
            return trans_en;
        case "lt":
            return trans_lt;
        default:
            return trans_en;
    }
};

export {getTranslation}