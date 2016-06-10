<?php

namespace McNetwork\Service;

Use McNetwork\Entity\CursosEntity as Entity;
use Zend\Session\Container;

class CursosService extends Entity {

    /**
     *
     * @var type 
     */
    protected $configList;

    /**
     * 
     * @param type $configList
     */
    public function setConfigList($configList) {
        $this->configList = $configList;
    }

    /**
     * 
     * @return type
     */
    public function getCursos() {

        $cache = $this->getServiceLocator()->get('Zend\Cache\Storage\Filesystem');

        $queryCache = $cache->getItem('cursosServiceGetCursos', $success);

        if (!$success) {

            $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

            $select = $sql->select('mc_posts');

            $where = [
                "mc_posts.post_type = 'course'",
            ];

            $select->where($where);

            $result = $this->toArrayResult($sql->prepareStatementForSqlObject($select)->execute());

            $cache->addItem('cursosServiceGetCursos', $result);

            return $result;
        }

        /*
         * se existir damos um decode no cache
         * se adicionarmos o segundo parâmetro como true geramos um array
         * se não adicionar o segundo parâmetro gera um objeto
         */
        return $queryCache;
    }

    /**
     * 
     * @param type $postId
     * @return type
     */
    public function getPost($postId) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('mc_posts');

        $where = [
            "mc_posts.ID = ?" => $postId,
        ];

        $select->where($where);

        return $sql->prepareStatementForSqlObject($select)->execute()->current();
    }

    /**
     * 
     * @param type $postId
     * @param type $metaKey
     * @return type
     */
    public function getPostMeta($postId, $metaKey = NULL) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $columns = [
            'meta_value'
        ];

        if (is_null($metaKey)) {

            $columns[] = "meta_key";
        }

        $select = $sql->select('mc_postmeta')->columns($columns);

        $where = [
            "mc_postmeta.post_id = ?" => $postId,
        ];

        if (!is_null($metaKey)) {

            $where["mc_postmeta.meta_key = ?"] = $metaKey;
        }

        $select->where($where);

        $result = $sql->prepareStatementForSqlObject($select)->execute();

        if (!is_null($metaKey)) {

            $result = $result->current();
        }

        return $result;
    }

    /**
     * 
     * @param type $userId
     * @param type $metaKey
     * @return type
     */
    public function getUserMetaPost($userId, $metaKey) {


        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('mc_usermeta')->columns(['meta_value']);

        $where = [
            "mc_usermeta.user_id = ?" => $userId,
            "mc_usermeta.meta_key = ?" => $metaKey,
        ];

        $select->where($where);

        $result = $sql->prepareStatementForSqlObject($select)->execute()->current();

        return $result;
    }

    /**
     * 
     * @param type $userId
     * @return type
     */
    public function excluirCursosUsuarioWP($userId) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $delete = $sql->delete('mc_usermeta');

        $where = [
            "mc_usermeta.user_id = ?" => trim($userId),
            "mc_usermeta.meta_key LIKE ?" => 'course_status%',
        ];

        $delete->where($where);
        return $sql->prepareStatementForSqlObject($delete)->execute();
    }

    /**
     * 
     * @param type $userId
     * @param type $metaKey
     * @param type $metaValue
     * @return type
     */
    public function inserirUserMeta($userId, $metaKey, $metaValue) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $insert = $sql->insert('mc_usermeta');
        $insert->values([
            'user_id' => $userId,
            'meta_key' => $metaKey,
            'meta_value' => $metaValue,
        ]);

        $sql->prepareStatementForSqlObject($insert)->execute();
        return $sql->getAdapter()->getDriver()->getLastGeneratedValue();
    }

    /**
     * 
     * @param type $postId
     * @param type $metaKey
     * @param type $metaValue
     * @return type
     */
    public function inserirPostMeta($postId, $metaKey, $metaValue) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $insert = $sql->insert('mc_postmeta');
        $insert->values([
            'post_id' => $postId,
            'meta_key' => $metaKey,
            'meta_value' => $metaValue,
        ]);

        $sql->prepareStatementForSqlObject($insert)->execute();
        return $sql->getAdapter()->getDriver()->getLastGeneratedValue();
    }

    /**
     * 
     * @param type $postId
     * @return type
     */
    public function getQuestionsTag($tag) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('mc_posts')->columns(['ID'])
                ->join('mc_term_relationships', 'mc_term_relationships.object_id = mc_posts.ID', [])
                ->join('mc_term_taxonomy', 'mc_term_taxonomy.term_taxonomy_id = mc_term_relationships.term_taxonomy_id', []);

        $where = [
            "mc_term_taxonomy.term_id = ?" => $tag,
            "mc_term_taxonomy.taxonomy" => 'question-tag',
        ];

        $select->where($where);

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $userId
     * @param type $metaKey
     * @param type $metaValue
     * @return type
     */
    public function inserirComments($postId, $commentContent, $userId) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $insert = $sql->insert('mc_comments');
        $insert->values([
            'comment_post_ID' => $postId,
            'comment_content' => $commentContent,
            'comment_approved' => 1,
            'user_id' => $userId,
        ]);

        $sql->prepareStatementForSqlObject($insert)->execute();
        return $sql->getAdapter()->getDriver()->getLastGeneratedValue();
    }
    
    

    /**
     * 
     * @return type
     */
    public function updateComments($values, $where) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $update = $sql->update('mc_comments');

        $update->set($values);
        
        $update->where($where);
        return $sql->prepareStatementForSqlObject($update)->execute();
    }
    
    /**
     * 
     * @return type
     */
    public function updateUserMetaPost($values, $where) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $update = $sql->update('mc_usermeta');

        $update->set($values);
        
        $update->where($where);
        return $sql->prepareStatementForSqlObject($update)->execute();
    }
    
    /**
     * 
     * @param type $postId
     * @param type $metaKey
     * @param type $metaValue
     * @return type
     */
    public function inserirCommentsMeta($commentId, $metaKey, $metaValue) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $insert = $sql->insert('mc_commentmeta');
        $insert->values([
            'comment_id' => $commentId,
            'meta_key' => $metaKey,
            'meta_value' => $metaValue,
        ]);

        $sql->prepareStatementForSqlObject($insert)->execute();
        return $sql->getAdapter()->getDriver()->getLastGeneratedValue();
    }
    
    /**
     * 
     * @param type $userId
     * @return type
     */
    public function excluirPostMeta($postId, $metaKey) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $delete = $sql->delete('mc_postmeta');

        $where = [
            "mc_postmeta.post_id = ?" => trim($postId),
            "mc_postmeta.meta_key = ?" => $metaKey,
        ];

        $delete->where($where);
        return $sql->prepareStatementForSqlObject($delete)->execute();
    }

}
