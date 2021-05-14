export const replaceItemAtIndex = (arr, index, newValue) => {
    return [...arr.slice(0, index), newValue, ...arr.slice(index + 1)];
}

export const deleteItemAtIndex = (arr, index) => {
    return [ ...arr.slice(0, index), ...arr.slice(index+1, arr.length ) ];
}