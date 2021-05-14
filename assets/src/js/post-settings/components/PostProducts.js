import { Button } from '@wordpress/components';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';

import { PostProduct } from './PostProduct';

function addProduct (props) {
    props.setMetaFieldValue([...props.allProducts, {}]);
    
}

function deleteProduct (index, props) {
    var products = [...props.allProducts];
    products.splice(index, 1);
    props.setMetaFieldValue(products);
}

function updateProduct (products, toUpdate, index) {
    console.log(products);
    console.log(toUpdate);
    console.log(index);
    var newProduct = { ...products[index], ...toUpdate };
    var newProducts = [...products, ...newProduct];
    products.setMetaValue(newProducts);
}

export const PostProducts = compose(
    withDispatch( (dispatch) => (
        {
            enableProducts: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { really_rich_results_product_enabled: value } }
                );
            },

            setProduct: (product, index, allProducts) => {
                var newProduct = { ...allProducts[index], ...product };
                var newProducts = [ ...allProducts ];
                
                newProducts[index] = newProduct;
                
                dispatch('core/editor').editPost(
                    { meta: { really_rich_results_product: newProducts } }
                );
            },

            addProduct: (allProducts) => {
                allProducts.push({});

                dispatch('core/editor').editPost(
                    { meta: { really_rich_results_product: allProducts } }
                );
            },

            deleteProduct: (index, allProducts) => {
                var products = [...allProducts];
                
                products.splice(index, 1);
                
                dispatch('core/editor').editPost(
                    { meta: { really_rich_results_product: products } }
                );
            },

            setMetaFieldValue: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { really_rich_results_product: value } }
                );
            }
        }
    )),
    withSelect( ( select, ownProps ) => ({
        productsEnabled: select('core/editor').getEditedPostAttribute('meta')['really_rich_results_product_enabled'],
        allProducts: select('core/editor').getEditedPostAttribute('meta')['really_rich_results_product'],
        singleProduct: (index) => {
            return select('core/editor').getEditedPostAttribute('meta')['really_rich_results_product'][index];
        },
    }) )
)((props) => {

    if ( ! Array.isArray(props.allProducts) ) { props.setMetaFieldValue([]) };

    if ( props.productsEnabled ) {
        return(
            <Fragment>
                {props.allProducts.map((value, index) => {

                    return (
                        <Fragment>
                            <PostProduct key={index} productProps={value} onChange={(product) => { props.setProduct( product, index, props.allProducts ) }} />
                            <Button onClick={() => { props.deleteProduct(index, props.allProducts) } }>Delete</Button>
                        </Fragment>
                    )
                })}
                <Button onClick={() => { props.addProduct(props.allProducts) } }>Add</Button>
            </Fragment>
        );
    }

    return null;
    
});