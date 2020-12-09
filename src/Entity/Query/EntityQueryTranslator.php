<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Query;

use Artister\System\Compiler\ExpressionVisitor;
use Artister\System\Compiler\ExpressionStringBuilder;
use Artister\System\Compiler\Expressions\Expression;
use Artister\System\Linq\IQueryable;


class EntityQueryTranslator extends ExpressionVisitor
{
    public string $Out              = '';
    private array $Parameters       = [];
    public array $OuterVariables    = [];

    public static function expressionToString(Expression $expression) : string
    {
        $visitor = new ExpressionStringBuilder();
        $visitor->visit($expression);
        return $visitor->__toString();
    }

    public function visitLambda(Expression $expression)
    {
        $this->Parameters = $expression->Parameters;
        $this->visit($expression->Body);
    }

    public function visitCall(Expression $expression)
    {
        $arguments = $expression->Arguments;
        $lastExpression = array_shift($arguments);
        if ($lastExpression)
        {
            $this->visit($lastExpression);
        }

        switch ($expression->Method)
        {
            case 'Where':
                $this->Out .= " WHERE ";
                break;
            case 'OrderBy':
            case 'OrderByDescending':
                $this->Out .= " ORDER BY ";
                break;
            case 'ThenBy':
            case 'ThenByDescending':
                $this->Out .= ", ";
                break;
            case 'GroupBy':
                $this->Out .= " GROUP BY ";
                break;
            default:
                # code...
                break;
        }
        
        foreach ($arguments as $argument)
        {
            $this->visit($argument);
        }

        if ($expression->Method == 'OrderBy' || $expression->Method == 'ThenBy')
        {
            $this->Out .= " ASC";
        } else if ($expression->Method == 'OrderByDescending' || $expression->Method == 'ThenByDescending') {
            $this->Out .= " DESC";
        }
    }

    public function visitArray(Expression $expression)
    {
        # code...
    }

    public function visitGroup(Expression $expression)
    {
        $this->Out .= "(";
        $this->visit($expression->Expression);
        $this->Out .= ")";
        //return $expression;
    }

    public function visitBinary(Expression $expression)
    {   
        $negation = '';
        switch ($expression->Name)
        {
            case '!=':
                $operator = '=';
                $negation = 'NOT ';
                break;
            case '==':
                $operator = '=';
                break;
            case '&&':
                $operator = 'AND';
                break;
            case '||':
                $operator = 'OR';
                break;
            default:
                $operator = $expression->Name;
                break;
        }
        $this->Out .= $negation;
        $this->visit($expression->Left);
        $this->Out .= ' '.$operator.' ';
        $this->visit($expression->Right);
        //return $expression;
    }

    public function visitProperty(Expression $expression)
    {
        if (in_array($expression->Parameter->Name, $this->Parameters))
        {
            $this->Out .= $expression->Property;
        }
        else
        {
            $this->Out .= '?';
        }
    }

    public function visitParameter(Expression $expression)
    {
        if (in_array($expression->Name, $this->Parameters))
        {
            $this->Out .= $expression->Name;
        }
        else
        {
            $this->OuterVariables[] = $expression->Value;
            $this->Out .= '?';
        }
    }

    public function visitConstant(Expression $expression)
    {
        if ($expression->Value instanceof IQueryable)
        {
            $this->Out .= "SELECT * FROM {$expression->Value->EntityType->getTableName()}";
        }
        else
        {
            $this->Out .= $expression->Value;
        }
    }

    public function visitUnary(Expression $expression)
    {
        $operator = $expression->Name;
        if ($expression->Name == '!')
        {
            $operator = 'NOT ';
        }
        $this->Out .= "{$operator}";
        $this->visit($expression->Operand);
        //return $expression;
    }

    public function __toString()
    {
        return $this->Out;
    }
}