import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

import App from './components/App';

const RenderReallyRichResultsPostSettings = () => (
    <PluginDocumentSettingPanel
        name="really-rich-results-post-settings-panel"
        title="Schema"
        className="really-rich-results-post-settings-panel"
    >
        <App />
    </PluginDocumentSettingPanel>
);
 
registerPlugin( 'really-rich-results-post-settings-panel', { render: RenderReallyRichResultsPostSettings } );