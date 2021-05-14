import { TabPanel } from '@wordpress/components';

import GeneralSettingsTab from './GeneralSettingsTab';
import PostTypeSettingsTab from './PostTypeSettingsTab';

import '../../../scss/PluginSettings.scss';

const settingsTabs = [
    {
        name: 'general',
        title: 'General Settings',
        className: 'general-tab',
    },
    {
        name: 'post-types',
        title: 'Post Type Settings',
        className: 'post-types-tab',
    }
];

export const App = () => {
    return(
        <div className="wrap">
            <h1>Really Rich Results Settings</h1>
            <TabPanel tabs={settingsTabs}>
            { (tab) => {
                switch(tab.name) {
                    case 'general':
                        return <GeneralSettingsTab />
                    case 'post-types':
                        return <PostTypeSettingsTab />
                    default:
                        return <p>Something broke.</p>
                }
            }}
        </TabPanel>
            
        </div>
        
    );
};