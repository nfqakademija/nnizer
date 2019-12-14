import Translator from 'bazinga-translator';
import transEN from '../translations/en.json';
import transLT from '../translations/lt.json';

const getTranslationsByLocale = (locale) => {
  switch (locale) {
    case 'en':
      return transEN;
    case 'lt':
      return transLT;
    default:
      return transEN;
  }
};

export const getTranslation = (key, domain = 'messages') => {
  const currentLocale = Translator.locale;
  const translations = getTranslationsByLocale(currentLocale);

  if (translations.translations[currentLocale][domain][key] != null) {
    return translations.translations[currentLocale][domain][key];
  }

  return key;
};

export default getTranslation;
