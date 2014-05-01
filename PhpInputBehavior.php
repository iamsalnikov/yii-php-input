<?php
/**
 * Поведение для работы с php://input
 * @author Alexey Salnikov <alexey@uley.pro>
 */

class PhpInputBehavior extends CBehavior
{
    /** @var  array */
    private $_phpInput = array();

    /**
     * Определим событие на начало обработки запроса
     * @return array
     */
    public function events()
    {
        return CMap::mergeArray(parent::events(), array(
            'onBeginRequest' => 'beginRequest'
        ));
    }

    /**
     * В начале обработки запроса получим данные из php://input
     * @param $event
     */
    public function beginRequest($event)
    {
        try {
            $this->_phpInput = CJSON::decode(file_get_contents("php://input"));
        } catch (Exception $e) {
            $this->_phpInput = array();
        }
    }

    /**
     * Получаем массив. В этом массиве данные из php://input
     * @param string $param - параметр, значение которого нужно получить
     * @param null $default - значение параметра по умолчанию
     * @return mixed. Если передан параметр, тогда вернет его значение или значение по умолчанию. Если
     * параметр не передан, тогда вернет весь массив phpInput
     */
    public function getInput($param = '', $default = null)
    {
        if ($param)
            return isset($this->_phpInput[$param]) ? $this->_phpInput[$param] : $default;
        else
            return $this->_phpInput;
    }
}