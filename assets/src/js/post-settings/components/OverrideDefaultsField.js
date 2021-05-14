import { ToggleControl } from '@wordpress/components';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';

export const OverrideDefaultsField = compose(
    withDispatch( (dispatch) => (
        {
            setMetaFieldValue: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { really_rich_resultsoverride_defaults: value } }
                );
            }
        }
    )),
    withSelect( ( select ) => ({
        metaFieldValue: select('core/editor').getEditedPostAttribute('meta')['really_rich_results_override_defaults'],
    }) )
)((props) => {
    return(
        <ToggleControl
            label="Override Plugin Settings"
            checked={props.metaFieldValue}
            onChange={(toggled) => {
                props.setMetaFieldValue(toggled);
        }} />
    );
});