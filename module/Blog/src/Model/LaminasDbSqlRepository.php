<?php

namespace Blog\Model;



use InvalidArgumentException;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Sql;
use RuntimeException;

class LaminasDbSqlRepository implements PostRepositoryInterface
{
    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var Post
     */
    private $postPrototype;

    /**
     * @param AdapterInterface $db
     */
    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Post $postPrototype
    ) {
        $this->db            = $db;
        $this->hydrator      = $hydrator;
        $this->postPrototype = $postPrototype;
    }
    /**
     * @inheritDoc
     */
    public function findAllPosts()
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('posts');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }

    /**
     * @inheritDoc
     */
    public function findPost($id)
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('posts');
        $select->where(['id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving blog post with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);
        $resultSet->initialize($result);
        $post = $resultSet->current();

        if (! $post) {
            throw new InvalidArgumentException(sprintf(
                'Blog post with identifier "%s" not found.',
                $id
            ));
        }

        return $post;
    }
}