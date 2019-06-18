<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-27 14:59
 */

namespace app\store\controller\goods;

use app\store\controller\Controller;
use app\store\model\Tags as TagsModel;

class Tags extends Controller
{
    private $model;

    /**
     * 添加规则组
     * @param $spec_name
     * @param $spec_value
     * @return array
     */
    public function addTags($name)
    {
        $this->model = new TagsModel();
        // 判断规格组是否存在
        if (!$specId = $this->model->getIdByName($name)) {
            // 新增规格组and规则值
            if ($this->model->save(['tags_name' => $name]))
                return $this->renderSuccess('', '', [
                    'tags_id' => (int)$this->model['id']
                ]);
            return $this->renderError();
        }
        return $this->renderSuccess('', '', [
            'tags_id' => (int)$specId
        ]);
    }
}