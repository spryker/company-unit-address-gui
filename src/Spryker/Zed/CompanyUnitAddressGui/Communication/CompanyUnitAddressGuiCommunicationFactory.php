<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\CompanyBusinessUnitAddressChoiceFormType;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\CompanyUnitAddressForm;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyBusinessUnitAddressFormDataProvider;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyUnitAddressFormDataProvider;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Table\CompanyUnitAddressTable;
use Spryker\Zed\CompanyUnitAddressGui\CompanyUnitAddressGuiDependencyProvider;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCountryFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class CompanyUnitAddressGuiCommunicationFactory extends AbstractCommunicationFactory
{
    public function createAddressTable(): CompanyUnitAddressTable
    {
        return new CompanyUnitAddressTable(
            $this->getCompanyUnitAddressPropelQuery(),
            $this->getCompanyUnitAddressTableConfigExpanderPlugins(),
            $this->getCompanyUnitAddressTableHeaderExpanderPlugins(),
            $this->getCompanyUnitAddressTableDataExpanderPlugins(),
        );
    }

    public function getCompanyUnitAddressFacade(): CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface
    {
        return $this->getProvidedDependency(
            CompanyUnitAddressGuiDependencyProvider::FACADE_COMPANY_UNIT_ADDRESS,
        );
    }

    public function getCompanyFacade(): CompanyUnitAddressGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(
            CompanyUnitAddressGuiDependencyProvider::FACADE_COMPANY,
        );
    }

    public function getCountryFacade(): CompanyUnitAddressGuiToCountryFacadeInterface
    {
        return $this->getProvidedDependency(
            CompanyUnitAddressGuiDependencyProvider::FACADE_COUNTRY,
        );
    }

    public function createCompanyUnitAddressForm(?int $idCompanyUnitAddress = null): FormInterface
    {
        $companyUnitAddressDataProvider = $this->createCompanyUnitAddressDataProvider();

        return $this->getFormFactory()->create(
            CompanyUnitAddressForm::class,
            $companyUnitAddressDataProvider->getData($idCompanyUnitAddress),
            $companyUnitAddressDataProvider->getOptions(),
        );
    }

    /**
     * @return array<\Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressEditFormExpanderPluginInterface>
     */
    public function getCompanyUnitAddressFormPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_FORM);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableConfigExpanderPluginInterface>
     */
    public function getCompanyUnitAddressTableConfigExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_CONFIG_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableHeaderExpanderPluginInterface>
     */
    public function getCompanyUnitAddressTableHeaderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_HEADER_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableDataExpanderPluginInterface>
     */
    public function getCompanyUnitAddressTableDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_DATA_EXPANDER);
    }

    public function createCompanyUnitAddressDataProvider(): CompanyUnitAddressFormDataProvider
    {
        return new CompanyUnitAddressFormDataProvider(
            $this->getCompanyUnitAddressFacade(),
            $this->getCompanyFacade(),
            $this->getCountryFacade(),
        );
    }

    public function createCompanyBusinessUnitAddressChoiceFormDataProvider(): CompanyBusinessUnitAddressFormDataProvider
    {
        return new CompanyBusinessUnitAddressFormDataProvider(
            $this->getCompanyUnitAddressFacade(),
        );
    }

    public function createCompanyBusinessUnitAddressChoiceFormType(): CompanyBusinessUnitAddressChoiceFormType
    {
        return new CompanyBusinessUnitAddressChoiceFormType();
    }

    public function getCompanyUnitAddressPropelQuery(): SpyCompanyUnitAddressQuery
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PROPEL_QUERY_COMPANY_UNIT_ADDRESS);
    }
}
