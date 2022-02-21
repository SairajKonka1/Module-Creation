
<?php
 
namespace I95dev\Module2\Model\Source;
 
class Customdropdown extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    public function getAllOptions() {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('--Select--'), 'value' => ''],
                ['label' => __('Option 1'), 'value' => 1],
                ['label' => __('Option 2'), 'value' => 2]
            ];
        }
        return $this->_options;
    }
}
