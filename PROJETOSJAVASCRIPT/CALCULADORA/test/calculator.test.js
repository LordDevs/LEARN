import { describe, expect, it } from 'vitest';
import {
  appendDecimal,
  appendDigit,
  appendOperator,
  clearExpression,
  deleteLastCharacter,
  evaluateExpression,
  handleInput,
  isOperator
} from '../js/calculator.js';
import { evaluate } from 'mathjs';

describe('calculator helpers', () => {
  it('identifica operadores válidos', () => {
    expect(isOperator('+')).toBe(true);
    expect(isOperator('-')).toBe(true);
    expect(isOperator('x')).toBe(false);
  });

  it('acrescenta dígitos preservando entradas anteriores', () => {
    expect(appendDigit('12', 3)).toBe('123');
  });

  it('reinicia após erro ao inserir dígitos', () => {
    expect(appendDigit('Erro', 9)).toBe('9');
  });

  it('permite começar com operador negativo', () => {
    expect(appendOperator('', '-')).toBe('-');
  });

  it('impede operadores múltiplos em sequência', () => {
    expect(appendOperator('7+', '+')).toBe('7+');
    expect(appendOperator('7+', '-')).toBe('7-');
    expect(appendOperator('', '+')).toBe('');
  });

  it('adiciona ponto decimal apenas uma vez por número', () => {
    expect(appendDecimal('')).toBe('0.');
    expect(appendDecimal('12')).toBe('12.');
    expect(appendDecimal('12.')).toBe('12.');
    expect(appendDecimal('12+')).toBe('12+0.');
  });

  it('apaga o último caractere com segurança', () => {
    expect(deleteLastCharacter('123')).toBe('12');
    expect(deleteLastCharacter('Erro')).toBe('');
  });

  it('limpa a expressão', () => {
    expect(clearExpression()).toBe('');
  });
});

describe('evaluateExpression', () => {
  it('calcula expressões matemáticas com math.js', () => {
    expect(evaluateExpression('1+2*3', evaluate)).toBe('7');
    expect(evaluateExpression('1/3', evaluate)).toBe('0.3333333333');
  });

  it('retorna erro para divisões inválidas', () => {
    expect(evaluateExpression('10/0', evaluate)).toBe('Erro');
  });

  it('retorna erro para expressões inválidas', () => {
    expect(evaluateExpression('2+(', evaluate)).toBe('Erro');
  });
});

describe('handleInput', () => {
  it('processa dígitos', () => {
    expect(handleInput('', 'valor', '5', evaluate)).toBe('5');
  });

  it('aplica operadores de forma segura', () => {
    let expression = handleInput('9', 'acao', '+', evaluate);
    expression = handleInput(expression, 'acao', '+', evaluate);
    expect(expression).toBe('9+');
  });

  it('avalia o resultado quando recebe "="', () => {
    expect(handleInput('9+1', 'acao', '=', evaluate)).toBe('10');
  });

  it('limpa quando recebe "c"', () => {
    expect(handleInput('99', 'acao', 'c', evaluate)).toBe('');
  });

  it('remove o último caractere com backspace', () => {
    expect(handleInput('123', 'acao', 'backspace', evaluate)).toBe('12');
  });

  it('interpreta ponto decimal', () => {
    let expression = handleInput('7+', 'acao', '.', evaluate);
    expect(expression).toBe('7+0.');
  });

  it('reinicia após uma operação inválida', () => {
    const result = handleInput('Erro', 'valor', '8', evaluate);
    expect(result).toBe('8');
  });
});

