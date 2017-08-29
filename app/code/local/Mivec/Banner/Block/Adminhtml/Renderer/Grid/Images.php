<?php
class Mivec_Banner_Block_Adminhtml_Renderer_Grid_Images extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	
    public function render(Varien_Object $row)
    {
        $value  = $row->getData($this->getColumn()->getIndex());  /* ten file */
        Mage::getBaseDir('media') . DS .'themevast' .DS .'banner' .DS;
        $url = Mage::getBaseUrl('media').$value;
        return "<a href=\"".$url ."\" target=\"_blank\"><img width=80 src=\"".$url."\" /></a>";
    }
}
