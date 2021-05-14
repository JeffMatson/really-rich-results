import {
    Panel,
    PanelBody,
    PanelRow,
    TextControl,
    TextareaControl,
    SelectControl
} from '@wordpress/components';

export function PostProduct(props) {
    return (
        <Panel>
            <PanelBody title={ 'name' in props.productProps && props.productProps.name !== '' ? props.productProps.name : 'Untitled Product'}>
                <Panel>
                    <PanelBody title={'Required Properties'}>
                        <PanelRow>
                            <TextControl
                                label={'Product Name'}
                                value={props.productProps.name}
                                onChange={(val) => {
                                    props.onChange({ name: val });
                                }}
                            />
                        </PanelRow>
                        <PanelRow>
                            <TextareaControl
                                label={'Product Description'}
                                value={props.productProps.description}
                                onChange={(val) => {
                                    props.onChange({ description: val });
                                }}
                            />
                        </PanelRow>
                        <PanelRow>
                            <TextControl
                                label={'Price'}
                                type={'number'}
                                min={'0.01'}
                                step={'0.01'}
                                value={props.productProps.price}
                                onChange={(val) => {
                                    props.onChange({ price: val });
                                }}
                            />
                        </PanelRow>
                        <PanelRow>
                            <TextControl
                                label={'Currency'}
                                value={props.productProps.currency}
                                onChange={(val) => {
                                    props.onChange({ currency: val });
                                }}
                            />
                        </PanelRow>
                    </PanelBody>
                </Panel>
                <Panel>
                    <PanelBody title={'Optional Properties'}>
                        <PanelRow>
                            <SelectControl
                                label={'Availability'}
                                value={props.productProps.availability}
                                options={[
                                    { label: 'In Stock', value: 'in_stock' },
                                    { label: 'Sold Out', value: 'sold_out' },
                                    { label: 'Out of Stock', value: 'out_of_stock' },
                                    { label: 'In Store Only', value: 'in_store_only' },
                                    { label: 'Online Only', value: 'online_only' },
                                    { label: 'Pre-Order', value: 'pre_order' },
                                    { label: 'Pre-Sale', value: 'pre_sale' },
                                    { label: 'Discontinued', value: 'discontinued' },
                                    { label: 'Limited Availability', value: 'limited_availability' },
                                ]}
                                onChange={(val) => {
                                    props.onChange({ availability: val });
                                }}
                            />
                        </PanelRow>
                        <PanelRow>
                            <TextControl
                                label={'MPN'}
                                value={props.productProps.mpn}
                                onChange={(val) => {
                                    props.onChange({ mpn: val });
                                }}
                            />
                        </PanelRow>
                        <PanelRow>
                            <TextControl
                                label={'SKU'}
                                value={props.productProps.sku}
                                onChange={(val) => {
                                    props.onChange({ sku: val });
                                }}
                            />
                        </PanelRow>
                        <PanelRow>
                            <TextControl
                                label={'Brand Name'}
                                value={props.productProps.brand}
                                onChange={(val) => {
                                    props.onChange({ brand: val });
                                }}
                            />
                        </PanelRow>
                    </PanelBody>
                </Panel>
            </PanelBody>
        </Panel>
    );
};