import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { PanelBody, PanelRow, SelectControl, ToggleControl, Button } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import { globalSettingsStore } from '../../stores/globalSettingsStore';

/**
 * Gets information from the global settings store.
 */
const applyWithSelect = withSelect( (select, ownProps) => ({
    postTypeSettings: select(globalSettingsStore.storeName).getSinglePostTypeSettings( ownProps.postType )
}));

/**
 * Dispatches events to the global settings store.
 */
const applyWithDispatch = withDispatch( (dispatch, ownProps) => ({
    setPostTypeSetting(val) {
        dispatch(globalSettingsStore.storeName).setPostTypeSetting( ownProps.postType, val);
    },
}));

/**
 * Renders the Post Type Settings panel.
 * 
 * @param {Object} props The props passed down from withSelect/withDispatch via compose.
 * 
 * @returns {Object}
 */
const PostTypeSettingsPanel = (props) => {
    const { label, enabled, schema_type, show_author, supports } = props.postTypeSettings;
    const supportedSchemaTypes = supports.schema_type;

    const saveSettings = (postTypeSettings) => {
        apiFetch({
            path: `/really_rich_results/v1/settings/post_types/${postTypeSettings.name}`,
            method: 'POST',
            data: postTypeSettings
        }).then( (res) => {
            console.log(res);
        });
    }

    return(
        <PanelBody initialOpen={false} title={label} className="post-type-panel">
            <PanelRow>
                <ToggleControl
                    label="Enable Schema Output"
                    checked={enabled}
                    onChange={(val) => props.setPostTypeSetting({ enabled: val })}
                />
            </PanelRow>

            {/**
             * If enabled, show schema options.
             */}
            {enabled &&
                <Fragment>
                    <PanelRow>
                        <SelectControl
                            label='Schema Type'
                            value={schema_type}
                            options={supportedSchemaTypes.map( (schemaType) => ( { value: schemaType, label: schemaType } ) ) }
                            onChange={(val) =>
                                props.setPostTypeSetting({ schema_type: val })
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label="Show Author Schema"
                            checked={show_author}
                            onChange={(val) =>
                                props.setPostTypeSetting({ show_author: val })
                            }
                        />
                    </PanelRow>
                </Fragment>
            }

            <PanelRow>
                <Button isPrimary onClick={() => saveSettings(props.postTypeSettings)}>{`Save ${label} Settings`}</Button>
            </PanelRow>
                
            </PanelBody>
    );
}

/**
 * Export component.
 */
export default compose(
    applyWithSelect,
    applyWithDispatch
)(PostTypeSettingsPanel);