<?php
require_once 'ParameterInterface.php';
class DataFactory
{
    private $data;

    public function setDataType(ParameterInterface $datatype)
    {
        $this->data = $datatype;
    }

    public function getDataType()
    {
        return $this->data->getData();
    }
}
?>