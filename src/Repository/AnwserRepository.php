<?php

namespace App\Repository;

use App\Entity\Anwser;
use App\Entity\Field;
use App\Entity\Form;
use App\Tools\Utils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Anwser>
 */
class AnwserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anwser::class);
    }

    /**
     * Retrieve all answers related to a form.
     * Here I volontary write native query to express my ability of quering natively in doctrine with Symfony and also for the query performance
     * I won't do the pagination query, the goal is not that for this test.
     * @param Form $form The form to retrieve answers for.
     * @return array An array of associative arrays containing the answer id, answer value, field label, and the date the answer was given.
     */
    public function getAnwsers(Form $form): array
    {
        $table = $this->getClassMetadata()->getTableName();
        $fieldTable = $this->getEntityManager()->getClassMetadata(Field::class)->getTableName();
        $query = sprintf(
            'SELECT 
                                    anwser.id as id,
                                    anwser.value as answer,
                                    field.label as field,
                                    identifier,
                                    DATE_FORMAT(answered_at, "%%d/%%m/%%Y at %%H:%%i") as answered_at
                                FROM %s anwser INNER JOIN %s field 
                                ON anwser.field_id = field.id 
                                WHERE field.form_id = %s',
            $table,
            $fieldTable,
            $form->getId()
        );

        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        try {
            $results =  $stmt->executeQuery()->fetchAllAssociative();
            return Utils::groupBy($results, 'identifier');
        } catch (\Exception $e) {
            return [];
        }
    }
}
