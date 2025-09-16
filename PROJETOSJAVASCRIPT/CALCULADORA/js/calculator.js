const missingEvaluator = () => {
  throw new Error('Uma função de avaliação segura é obrigatória.');
};

export const OPERATORS = Object.freeze(['+', '-', '*', '/']);
const OPERATOR_SET = new Set(OPERATORS);

function normalizeExpression(value = '') {
  if (value === 'Erro' || value == null) {
    return '';
  }

  return value.toString();
}

export function isOperator(value) {
  return OPERATOR_SET.has(value);
}

export function clearExpression() {
  return '';
}

export function appendDigit(expression, digit) {
  const base = normalizeExpression(expression);
  return `${base}${digit.toString()}`;
}

export function appendOperator(expression, operator) {
  const base = normalizeExpression(expression);

  if (!isOperator(operator)) {
    return base;
  }

  if (!base) {
    return operator === '-' ? operator : base;
  }

  const lastChar = base.slice(-1);
  if (isOperator(lastChar)) {
    return `${base.slice(0, -1)}${operator}`;
  }

  return `${base}${operator}`;
}

export function appendDecimal(expression) {
  const base = normalizeExpression(expression);

  if (!base) {
    return '0.';
  }

  const lastChar = base.slice(-1);
  if (lastChar === '.') {
    return base;
  }

  if (isOperator(lastChar)) {
    return `${base}0.`;
  }

  const lastNumber = base.split(/[-+*/]/).pop();
  if (lastNumber && lastNumber.includes('.')) {
    return base;
  }

  return `${base}.`;
}

export function deleteLastCharacter(expression) {
  const base = normalizeExpression(expression);
  if (!base) {
    return '';
  }

  return base.slice(0, -1);
}

export function evaluateExpression(expression, evaluateFn = missingEvaluator) {
  const base = normalizeExpression(expression).trim();

  if (!base) {
    return '';
  }

  try {
    const result = evaluateFn(base);

    if (typeof result === 'number') {
      if (!Number.isFinite(result)) {
        return 'Erro';
      }

      return Number.isInteger(result) ? result.toString() : Number(result.toFixed(10)).toString();
    }

    if (result != null && typeof result.toString === 'function') {
      const stringValue = result.toString();
      if (!stringValue || stringValue === 'Infinity' || stringValue === '-Infinity') {
        return 'Erro';
      }

      return stringValue;
    }

    return 'Erro';
  } catch (error) {
    return 'Erro';
  }
}

export function handleInput(expression, type, value, evaluateFn = missingEvaluator) {
  const base = normalizeExpression(expression);

  if (type === 'valor') {
    return appendDigit(base, value);
  }

  if (type === 'acao') {
    if (value === 'c') {
      return clearExpression();
    }

    if (value === '=') {
      return evaluateExpression(base, evaluateFn);
    }

    if (value === '.') {
      return appendDecimal(base);
    }

    if (value === 'backspace') {
      return deleteLastCharacter(base);
    }

    if (isOperator(value)) {
      return appendOperator(base, value);
    }
  }

  return base;
}
