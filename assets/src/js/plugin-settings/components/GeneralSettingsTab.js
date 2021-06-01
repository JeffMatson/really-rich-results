import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Fragment, useState } from '@wordpress/element';
import { TextControl, Button, BaseControl, Panel, PanelBody, PanelRow } from '@wordpress/components';
import { trash, plus, check } from '@wordpress/icons';
import apiFetch from '@wordpress/api-fetch';

import { globalSettingsStore } from '../../stores/globalSettingsStore';

/**
 * Gets information from the global settings store.
 */
const applyWithSelect = withSelect( (select) => ({
    globalSettings: select(globalSettingsStore.storeName).getGlobalSettings(),
    postTypeSettings: select(globalSettingsStore.storeName).getPostTypeSettings()
}));

/**
 * Dispatches events to the global settings store.
 */
const applyWithDispatch = withDispatch( (dispatch) => ({
    setGlobalSetting: (val) => {
        dispatch(globalSettingsStore.storeName).setGlobalSetting(val);
    },
    setGlobalOrgSetting: (val) => {
        dispatch(globalSettingsStore.storeName).setGlobalOrgSetting(val);
    },
    setGlobalOrgAddressSetting: (val) => {
        dispatch(globalSettingsStore.storeName).setGlobalOrgAddressSetting(val);
    },
    addEmptyGlobalOrgSameAs: (val) => {
        dispatch(globalSettingsStore.storeName).updateSameAs(val);
    },
    updateSameAs(val, index) {
        dispatch(globalSettingsStore.storeName).updateSameAs(val, index);
    },
    deleteSameAs( index ) {
        dispatch(globalSettingsStore.storeName).deleteSameAs(index);
    }
}));

/**
 * Renders the General Settings tab.
 * 
 * @param {Object} props The props passed down from withSelect/withDispatch via compose.
 * 
 * @returns {Object}
 */
const GeneralSettingsTab = (props) => {

    const [saveButtonProps, setSaveButtonProps] = useState({
        icon: false,
        isBusy: false,
        className: 'save-changes',
        text: 'Save Changes'
    });

    /**
     * Saves global settings.
     * TODO: Improve save state feedback/report errors.
     * TODO: Validation/sanitization for better feedback before hitting the API.
     * 
     * @param {Object} globalSettings Settings from the global settings store.
     * 
     * @returns {boolean} True if success.
     */
    const saveSettings = (globalSettings) => {
        setSaveButtonProps({
            icon: false,
            isBusy: true,
            className: 'save-changes',
            text: 'Saving'
        });

        apiFetch({
            path: '/really_rich_results/v1/settings/site',
            method: 'POST',
            data: globalSettings
        }).then( (res) => {
            // TODO: actual real feedback for errors.
            setSaveButtonProps({
                icon: check,
                isBusy: false,
                className: 'save-changes',
                text: 'Saved Successfully'
            });
        });
    }

    const SaveButton = (props) => {
        return <Button isBusy={saveButtonProps.isBusy} icon={saveButtonProps.icon} className={saveButtonProps.className} isPrimary onClick={props.onClick}>{saveButtonProps.text}</Button>
    }

    return(
        <Fragment>
            <Panel className="rrr-general-settings">
                <PanelBody title="Organization Details">
                    <TextControl
                        label='Organization Name'
                        value={props.globalSettings.organization.name}
                        onChange={ (val) => props.setGlobalOrgSetting({ name: val }) }
                    />
                    <TextControl
                        label='Organization URL'
                        value={props.globalSettings.organization.url}
                        onChange={ (val) => props.setGlobalOrgSetting({ url: val }) }
                    />
                    <BaseControl>
                        <BaseControl.VisualLabel>Additional URLs (SameAs)</BaseControl.VisualLabel>
                        {props.globalSettings.organization.sameAs.map( (sameAs, index) =>
                            <PanelRow>
                                <TextControl
                                    className="same-as-field"
                                    value={sameAs}
                                    onChange={(val) => props.updateSameAs(val, index)}
                                />
                                <Button className="same-as-remove" icon={trash} onClick={ () => props.deleteSameAs(index) } />
                            </PanelRow>
                        )}
                        <PanelRow className="same-as-add">
                            <Button isSecondary icon={plus} text="Add URL" onClick={ () => { props.addEmptyGlobalOrgSameAs('');} } />
                        </PanelRow>
                        
                    </BaseControl>
                    <TextControl
                        label='Organization DUNS'
                        value={props.globalSettings.organization.duns}
                        onChange={ (val) => props.setGlobalOrgSetting({ duns: val }) }
                    />
                </PanelBody>

                <PanelBody title="Organization Address">
                    <TextControl
                        label='Street'
                        value={props.globalSettings.organization.address.street}
                        onChange={ (val) => props.setGlobalOrgAddressSetting({ street: val }) }
                    />
                    <TextControl
                        label='PO Box'
                        value={props.globalSettings.organization.address.poBox}
                        onChange={ (val) => props.setGlobalOrgAddressSetting({ poBox: val }) }
                    />
                    <PanelRow>
                        <TextControl
                            className="org-city"
                            label='City'
                            value={props.globalSettings.organization.address.city}
                            onChange={ (val) => props.setGlobalOrgAddressSetting({ city: val }) }
                        />
                        <TextControl
                            className="org-state"
                            label='State'
                            value={props.globalSettings.organization.address.state}
                            onChange={ (val) => props.setGlobalOrgAddressSetting({ state: val }) }
                        />
                        <TextControl
                            className="org-postal-code"
                            label='Postal Code'
                            value={props.globalSettings.organization.address.postalCode}
                            onChange={ (val) => props.setGlobalOrgAddressSetting({ postalCode: val }) }
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label='Country'
                            value={props.globalSettings.organization.address.country}
                            onChange={ (val) => props.setGlobalOrgAddressSetting({ country: val }) }
                        />
                    </PanelRow>
                </PanelBody>

            </Panel>

            <SaveButton onClick={() => saveSettings({ ...props.globalSettings })} />
        </Fragment>
    );
}

/**
 * Export component.
 */
export default compose(
    applyWithDispatch,
    applyWithSelect
)(GeneralSettingsTab);