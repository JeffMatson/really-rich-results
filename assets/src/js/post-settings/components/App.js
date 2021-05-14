import { SchemaTypeField } from './SchemaTypeField';
import { OverrideDefaultsField } from './OverrideDefaultsField';
import { PostProducts } from './PostProducts';
import { EnableProductsField } from './EnableProductsField';

export default function App(props) {
    return (
        <div className="plugin-sidebar-content">
            <h3>General Schema Settings</h3>
            <OverrideDefaultsField />
            <SchemaTypeField />
            
            <h3>Product Schema Settings</h3>
            <EnableProductsField />
            <PostProducts />
        </div>
    );
}