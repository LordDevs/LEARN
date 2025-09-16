import { handleInput, OPERATORS } from './calculator.js';
import { evaluate } from 'https://cdn.jsdelivr.net/npm/mathjs@11.11.0/+esm';

const DISPLAY_DEFAULT = '0';
const THEME_STORAGE_KEY = 'calculator-theme';
const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

const display = document.getElementById('display');
const buttons = document.querySelectorAll('[data-type]');
const themeToggle = document.getElementById('theme-toggle');
const themeLabel = themeToggle?.querySelector('.theme-toggle__label');
const themeIcon = themeToggle?.querySelector('.theme-toggle__icon');

let expression = '';

function updateDisplay(value) {
  const normalized = value == null ? '' : value.toString();
  const isError = normalized === 'Erro';

  display.value = normalized === '' ? DISPLAY_DEFAULT : normalized;
  display.dataset.state = isError ? 'error' : 'default';
  display.setAttribute('aria-live', isError ? 'assertive' : 'polite');
}

function safeStoreTheme(theme) {
  try {
    localStorage.setItem(THEME_STORAGE_KEY, theme);
  } catch (error) {
    // Storage may be unavailable; ignore.
  }
}

function safeReadTheme() {
  try {
    return localStorage.getItem(THEME_STORAGE_KEY);
  } catch (error) {
    return null;
  }
}

function applyTheme(theme) {
  const target = document.documentElement;
  const normalizedTheme = theme === 'dark' ? 'dark' : 'light';
  const isDark = normalizedTheme === 'dark';

  target.setAttribute('data-bs-theme', normalizedTheme);

  if (themeToggle) {
    themeToggle.setAttribute('aria-pressed', String(isDark));
    themeToggle.classList.toggle('btn-outline-primary', !isDark);
    themeToggle.classList.toggle('btn-outline-light', isDark);
  }

  if (themeLabel) {
    themeLabel.textContent = `Tema: ${isDark ? 'Escuro' : 'Claro'}`;
  }

  if (themeIcon) {
    themeIcon.textContent = isDark ? '🌙' : '🌞';
  }
}

function initializeTheme() {
  const storedTheme = safeReadTheme();
  const preferredTheme = storedTheme || (mediaQuery.matches ? 'dark' : 'light');

  applyTheme(preferredTheme);

  if (!storedTheme) {
    mediaQuery.addEventListener('change', (event) => {
      applyTheme(event.matches ? 'dark' : 'light');
    });
  }
}

function toggleTheme() {
  const currentTheme = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
  const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';

  applyTheme(nextTheme);
  safeStoreTheme(nextTheme);
}

function handleButtonClick(event) {
  const { type, value } = event.currentTarget.dataset;
  expression = handleInput(expression, type, value, evaluate);
  updateDisplay(expression);
}

function handleKeyboard(event) {
  const { key } = event;

  if (/^\d$/.test(key)) {
    expression = handleInput(expression, 'valor', key, evaluate);
    updateDisplay(expression);
    event.preventDefault();
    return;
  }

  if (key === 'Enter' || key === '=') {
    expression = handleInput(expression, 'acao', '=', evaluate);
    updateDisplay(expression);
    event.preventDefault();
    return;
  }

  if (key === 'Escape') {
    expression = handleInput(expression, 'acao', 'c', evaluate);
    updateDisplay(expression);
    event.preventDefault();
    return;
  }

  if (key === 'Backspace') {
    expression = handleInput(expression, 'acao', 'backspace', evaluate);
    updateDisplay(expression);
    event.preventDefault();
    return;
  }

  if (key === '.' || key === ',') {
    expression = handleInput(expression, 'acao', '.', evaluate);
    updateDisplay(expression);
    event.preventDefault();
    return;
  }

  if (OPERATORS.includes(key)) {
    expression = handleInput(expression, 'acao', key, evaluate);
    updateDisplay(expression);
    event.preventDefault();
  }
}

buttons.forEach((button) => {
  button.addEventListener('click', handleButtonClick);
});

document.addEventListener('keydown', handleKeyboard);

if (themeToggle) {
  themeToggle.addEventListener('click', toggleTheme);
}

initializeTheme();
updateDisplay(expression);

