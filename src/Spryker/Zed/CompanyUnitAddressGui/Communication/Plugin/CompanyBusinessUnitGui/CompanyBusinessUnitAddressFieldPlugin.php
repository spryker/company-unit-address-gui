<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Plugin\CompanyBusinessUnitGui;

use Spryker\Zed\CompanyBusinessUnitGuiExtension\Communication\Plugin\CompanyBusinessUnitFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyUnitAddressGui\Communication\CompanyUnitAddressGuiCommunicationFactory getFactory()
 */
class CompanyBusinessUnitAddressFieldPlugin extends AbstractPlugin implements CompanyBusinessUnitFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds company business unit address field.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $formType = $this->getFactory()
            ->createCompanyBusinessUnitAddressChoiceFormType();

        $dataProvider = $this->getFactory()
            ->createCompanyBusinessUnitAddressChoiceFormDataProvider();

        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer */
        $companyBusinessUnitTransfer = $builder->getData();
        $dataProvider->getData($companyBusinessUnitTransfer);

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions($companyBusinessUnitTransfer->getFkCompany()),
        );
    }
}
