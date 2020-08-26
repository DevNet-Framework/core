<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Query;

use Artister\DevNet\Entity\Internal\EntityMapper;
use Artister\System\Database\DbConnection;
use Artister\System\Linq\Expressions\Expression;
use Artister\System\Linq\IQueryProvider;
use Artister\System\Collections\Enumerator;

class EntityQueryProvider implements IQueryProvider
{
    private DbConnection $Connection;
    private EntityMapper $Mapper;

    public function __construct(EntityMapper $mapper)
    {
        $this->Connection   = $mapper->Connection;
        $this->Mapper       = $mapper;
    }

    public function CreateQuery(string $resultType, Expression $expression = null)
    {
        return new EntityQuery($resultType, $this, $expression);
    }

    public function execute(string $resultType, Expression $expression)
    {
        $translator = new EntityQueryTranslator();
        $translator->visit($expression);
        $slq = $translator->Out;
        
        $this->Connection->open();
        $command = $this->Connection->createCommand($slq);
        if ($translator->OuterVariables)
        {
            $command->addParameters($translator->OuterVariables);
        }

        $dbReader = $command->executeReader($resultType);

        if (!$dbReader)
        {
            return new Enumerator();
        }

        $entities = [];
        foreach ($dbReader as $entity)
        {
            $entities[] = $entity;
            $entry = $this->Mapper->EntityStateManager->getEntry($entity);
            if ($entry)
            {
                $this->Mapper->EntityStateManager->addEntry($entity);
            }
        }

        return new Enumerator($entities);
    }

    public function GetQueryText(Expression $expression) : string
    {
        $translator = new EntityQueryTranslator();
        $translator->visit($expression);
        return $translator->Out;
    }
}