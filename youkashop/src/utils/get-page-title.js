import defaultSettings from '../settings'

const title = defaultSettings.title || '悠咖';

export default function getPageTitle(pageTitle) {
  if (pageTitle) {
    return `${pageTitle} - ${title}`
  }
  return `${title}`
}
