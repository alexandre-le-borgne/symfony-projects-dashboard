<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Script;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * ScriptRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScriptRepository extends \Doctrine\ORM\EntityRepository
{
    public function search(array $terms, $limit = 10)
    {
        if (empty($terms)) {
            return $this->findBy([], ['id' => 'desc'], $limit);
        }

        $query = 'SELECT s.* FROM script s';
        $query .= ' WHERE (s.title LIKE :term_0 OR EXISTS(
        SELECT * FROM tag t INNER JOIN script_tag st ON st.tag_id = t.id AND st.script_id = s.id 
        WHERE t.name LIKE :term_0)) ';

        for ($i = 1; $i < count($terms); ++$i) {
            $query .= ' AND (s.title LIKE :term_' . $i . ' OR EXISTS(
        SELECT * FROM tag t INNER JOIN script_tag st ON st.tag_id = t.id AND st.script_id = s.id 
        WHERE t.name LIKE :term_' . $i . '))';
        }
        $query .= ' ORDER BY s.id DESC LIMIT :limit ';

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Script::class, 's');
        $params = array_combine(
            array_map(function ($v) {
                return 'term_' . $v;
            }, array_keys($terms)),
            array_map(function ($v) {
                return '%' . $v . '%';
            }, $terms));
        $params['limit'] = $limit;

        return $this->getEntityManager()->createNativeQuery($query, $rsm)->setParameters($params)->getResult();
    }
}
