import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Panel } from '@wordpress/components';

import { globalSettingsStore } from '../../stores/globalSettingsStore';

import PostTypeSettingsPanel from './PostTypeSettingsPanel';

/**
 * Gets information from the global settings store.
 */
const applyWithSelect = withSelect( (select) => ({
    postTypeSettings: select(globalSettingsStore.storeName).getPostTypeSettings()
}));

/**
 * Renders the Post Type Settings tab.
 * 
 * @param {Object} props The props passed down from withSelect/withDispatch via compose.
 * 
 * @returns {Object}
 */
const PostTypeSettingsTab = (props) => {
    return(
        <Panel>
            {Object.keys(props.postTypeSettings).map((postType) =>
                <PostTypeSettingsPanel key={postType} postType={postType}/>
            )}
        </Panel>
    );
}

/**
 * Export component.
 */
export default compose(
    applyWithSelect
)(PostTypeSettingsTab);