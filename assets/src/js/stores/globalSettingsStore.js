/**
 * TODO: Break out state props (actions, selectors, etc.)
 * TODO: Simplify nesting props in reducer with immer.
 */

import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';
import produce from 'immer';

const storeName = 'really_rich_results/global-settings';

const defaultState = {
    organization: {
        name: '',
        url: '',
        duns: '',
        address: {
            street: '',
            poBox: '',
            city: '',
            state: '',
            postalCode: '',
            country: '',
        },
        sameAs: []
    },
    postTypes: [],
}

/**
 * Actions to be used by the data store.
 */
const actions = {

    /**
     * Gets all global settings. 
     * 
     * @returns {Object}
     */
    getGlobalSettings() {
        return {
            type: 'GET_GLOBAL_SETTINGS'
        }
    },

    /**
     * Gets all post type settings.
     */
    getPostTypeSettings() {
        return {
            type: 'GET_POST_TYPE_SETTINGS'
        }
    },

    /**
     * Replaces all global settings with the provided object.
     * 
     * @param {Object} globalSettings 
     * @returns {Object}
     */
    setGlobalSettings(globalSettings) {
        return {
            type: 'SET_GLOBAL_SETTINGS',
            globalSettings,
        }
    },

    /**
     * Sets a single global setting inside the global settings data store.
     * 
     * @param {*} val Object to merge with the existing settings.
     * @returns {Object}
     */
    setGlobalSetting(val) {
        return {
            type: 'SET_GLOBAL_SETTING',
            val
        }
    },

    setPostTypeSetting(postType, val) {
        return {
            type: 'SET_POST_TYPE_SETTING',
            postType,
            val
        }
    },

    setPostTypeSettings(val) {
        return {
            type: 'SET_POST_TYPE_SETTINGS',
            val
        }
    },

    setGlobalOrgSetting(val) {
        return {
            type: 'SET_GLOBAL_ORG_SETTING',
            val
        }
    },

    setGlobalOrgAddressSetting(val) {
        return {
            type: 'SET_GLOBAL_ORG_ADDRESS_SETTING',
            val
        }
    },

    updateSameAs(val, index) {
        return {
            type: 'UPDATE_GLOBAL_ORG_SAME_AS',
            val,
            index
        }
    },
};

/**
 * Selectors
 */
const selectors = {
    /**
     * Gets all global settings from state.
     * 
     * @param {Object} state The state.
     * @returns {Object}
     */
    getGlobalSettings( state ) {
        return state;
    },

    /**
     * Gets the post type settings from state.
     * 
     * @param {Object} state The state.
     * @returns {Object}
     */
    getPostTypeSettings( state ) {
        return state.postTypes;
    },

    /**
     * Gets the post type settings from state.
     * 
     * @param {Object} state The state.
     * @param {string} postType The post type.
     * 
     * @returns {Object}
     */
    getSinglePostTypeSettings( state, postType ) {
        return state.postTypes[postType];
    },

    /**
     * Gets a single setting from state.
     * 
     * @param {Object} state   The state.
     * @param {string} setting The setting name.
     * 
     * @returns {*}
     */
    getGlobalSetting( state, setting ) {
        return state[ setting ];
    },

    /**
     * Gets an organization setting from state.
     * 
     * @param {Object} state   The state.
     * @param {string} setting The setting name.
     * 
     * @returns {*}
     */
    getGlobalOrgSetting( state, setting ) {
        return state.organization[ setting ];
    },


    /**
     * Gets the a setting from the organization's address.
     * 
     * @param {Object} state   The state.
     * @param {string} setting The setting name.
     * 
     * @returns {*}
     */
    getGlobalOrgAddressSetting( state, setting ) {
        return state.organization.address[ setting ];
    }
}

/**
 * Controls
 */
const controls = {
    GET_GLOBAL_SETTINGS( action ) {
        return apiFetch( { path: '/really_rich_results/v1/settings/site' } );
    },
    GET_POST_TYPE_SETTINGS( action ) {
        return apiFetch( { path: '/really_rich_results/v1/settings/post_types' } );
    }
}

/**
 * Resolvers
 */
const resolvers = {
    *getGlobalSettings() {
        const globalSettings = yield actions.getGlobalSettings();
        return actions.setGlobalSettings(globalSettings);
    },
    *getPostTypeSettings() {
        const postTypeSettings = yield actions.getPostTypeSettings();
        return actions.setPostTypeSettings(postTypeSettings);
    }
};

/**
 * The redux store that will be registered.
 */
const store = createReduxStore(storeName, {
    reducer( state = defaultState, action ) {
        switch ( action.type ) {
            case 'GET_GLOBAL_SETTINGS':
                return state;
            case 'SET_GLOBAL_SETTINGS':
                return {
                    ...state,
                    ...action.globalSettings
                }
            case 'SET_GLOBAL_SETTING':
                return {
                    ...state,
                    ...action.val
                }
            case 'SET_GLOBAL_ORG_SETTING':
                return {
                    ...state,
                    organization: {
                        ...state.organization,
                        ...action.val
                    }
                }
            case 'SET_GLOBAL_ORG_ADDRESS_SETTING':
                return {
                    ...state,
                    organization: {
                        ...state.organization,
                        address: {
                            ...state.organization.address,
                            ...action.val
                        }
                    }
                }
            case 'UPDATE_GLOBAL_ORG_SAME_AS':
                if ( action.index === undefined ) {
                    return produce(state, draft => {
                        draft.organization.sameAs.push(action.val);
                    })
                } else {
                    return produce(state, draft => {
                        draft.organization.sameAs[action.index] = action.val
                    })
                }
            case 'SET_POST_TYPE_SETTINGS':
                return {
                    ...state,
                    postTypes: {
                        ...state.postTypes,
                        ...action.val
                    }
                }
            case 'SET_POST_TYPE_SETTING':
                return produce(state, draft => {
                    draft.postTypes[action.postType] = { ...state.postTypes[action.postType], ...action.val }
                })
        }

        return state;
    },

    actions: actions,
    selectors: selectors,
    controls: controls,
    resolvers: resolvers,
});

register(store);

export const globalSettingsStore = { storeName };