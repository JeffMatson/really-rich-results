import { ToggleControl } from '@wordpress/components';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';

export const EnableProductsField = compose(
    withDispatch( (dispatch) => (
        {
            setMetaFieldValue: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { really_rich_results_product_enabled: value } }
                );
            }
        }
    )),
    withSelect( ( select ) => ({
        metaFieldValue: select('core/editor').getEditedPostAttribute('meta')['really_rich_results_product_enabled'],
    }) )
)((props) => {
    return(
        <ToggleControl
            label="Include Custom Products"
            checked={props.metaFieldValue}
            onChange={(toggled) => {
                props.setMetaFieldValue(toggled);
        }} />
    );
});