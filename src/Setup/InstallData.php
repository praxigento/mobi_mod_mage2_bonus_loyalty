<?php
/**
 * Populate DB schema with module's initial data
 * .
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Praxigento\BonusLoyalty\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Praxigento\Accounting\Lib\Entity\Type\Operation as TypeOperation;
use Praxigento\Bonus\Base\Lib\Entity\Type\Calc as TypeCalc;
use Praxigento\Bonus\Loyalty\Lib\Config as Cfg;

class InstallData extends \Praxigento\Core\Setup\Data\Base
{
    private function _addAccountingOperationsTypes()
    {
        $this->_getConn()->insertArray(
            $this->_getTableName(TypeOperation::ENTITY_NAME),
            [TypeOperation::ATTR_CODE, TypeOperation::ATTR_NOTE],
            [
                [Cfg::CODE_TYPE_OPER_BONUS_LOYALTY, 'Loyalty bonus.']
            ]
        );
    }

    private function _addBonusCalculationsTypes()
    {
        $this->_getConn()->insertArray(
            $this->_getTableName(TypeCalc::ENTITY_NAME),
            [TypeCalc::ATTR_CODE, TypeCalc::ATTR_NOTE],
            [
                [Cfg::CODE_TYPE_CALC_COMPRESSION, 'Compression for Loyalty bonus.'],
                [Cfg::CODE_TYPE_CALC_QUALIFICATION, 'Qualification parameters for Loyalty bonus.'],
                [Cfg::CODE_TYPE_CALC_BONUS, 'Loyalty bonus itself.']
            ]
        );
    }

    protected function _setup(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->_addAccountingOperationsTypes();
        $this->_addBonusCalculationsTypes();
    }
}