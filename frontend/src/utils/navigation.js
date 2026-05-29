let _navigate = null;

export const setNavigate = (fn) => { _navigate = fn; };

export const navigateBack = () => _navigate?.(-1);
