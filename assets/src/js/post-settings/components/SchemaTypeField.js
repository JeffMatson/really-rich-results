import { SelectControl } from '@wordpress/components';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';

export const SchemaTypeField = compose(
    withDispatch( (dispatch) => ({
        setMetaFieldValue: (value) => {
            dispatch('core/editor').editPost(
                { meta: { really_rich_results_schema_type: value } }
            );
        }
    })),
    withSelect( ( select ) => ({
        metaFieldValue: select('core/editor').getEditedPostAttribute('meta')['really_rich_results_schema_type'],
    }) )
)((props) => {
    return(
        <SelectControl
            label="Schema Type"
            value={props.metaFieldValue}
            options={[
                { label: 'Use Default',       value: 'default' },
                { label: 'WebPage',           value: 'WebPage' },
                { label: 'Article',           value: 'Article' },
                { label: 'Product',           value: 'Product' },
                { label: 'Organization',      value: 'Organization' },
                { label: 'Review',            value: 'Review' },
                { label: 'Place',             value: 'Place' },
                { label: 'Event',             value: 'Event' },
                { label: 'AboutPage',         value: 'AboutPage' },
                { label: 'CheckoutPage',      value: 'CheckoutPage' },
                { label: 'ContactPage',       value: 'ContactPage' },
                { label: 'FAQPage',           value: 'FAQPage' },
                { label: 'ProfilePage',       value: 'ProfilePage' },
                { label: 'SearchResultsPage', value: 'SearchResultsPage' }
            ]}
            onChange={(schemaType) => {
                props.setMetaFieldValue(schemaType);
            }}
        />
    );
});