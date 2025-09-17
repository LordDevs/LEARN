const rootElement = document.documentElement;
const colorSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');

const applyTheme = (isDarkMode) => {
  rootElement.dataset.bsTheme = isDarkMode ? 'dark' : 'light';
};

const handlePreferenceChange = (event) => {
  applyTheme(event.matches);
};

export const initTheme = () => {
  applyTheme(colorSchemeQuery.matches);

  if (typeof colorSchemeQuery.addEventListener === 'function') {
    colorSchemeQuery.addEventListener('change', handlePreferenceChange);
  } else if (typeof colorSchemeQuery.addListener === 'function') {
    colorSchemeQuery.addListener(handlePreferenceChange);
  }
};
