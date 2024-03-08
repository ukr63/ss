'use client';

export const initialState = {
    language: 'EN'
}

const SettingsReducer = (
    state: object = initialState,
    actions: {type: string, payload: any}
) : any => {
    const { payload, type } = actions;

    switch (type) {
        case 'settings/setProps':
            return { ...state, ...payload };
        default:
            return state;
    }
}

export default SettingsReducer;