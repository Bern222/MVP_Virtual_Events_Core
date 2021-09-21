const configAuthentication = {
    type: enumAuthenticationTypes.EMAIL,
    header: 'Please Login Below',
    description: 'Enter your email to sign in.',
    forgotPassword: false,
    success: {
        action: enumButtonActions.OPEN_ROUTE,
        actionParams: enumRoutes.LOGIN
    },
    fail: {
        messageUserMissing: 'You must enter both an email address and a password to log in.',
        messageUserUnknown: 'User Email or Password is invalid, please try again.',
        messageUserBadPassword: 'User Email or Password is invalid, please try again.',
        messageUserInvalidated: 'Your account has not been validated by the administrator.',
        messageUserDisabled: 'Your account has been disabled.',
        messageDefault: 'Unknown Error. Please try again later.',
    }
};