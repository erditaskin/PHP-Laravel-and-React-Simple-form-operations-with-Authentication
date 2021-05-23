import React from 'react';
import { Route, Redirect } from 'react-router-dom';

import { isLogged } from '../api/login';
import BaseLayout from '../components/Layout/Base';

export const PrivateRoute = ({ component: Component, ...rest }) => {
  return (
    <Route
      {...rest}
      render={(props) =>
        isLogged ? (
          <BaseLayout>
            <Component {...props} />
          </BaseLayout>
        ) : (
          <Redirect to="/login" />
        )
      }
    />
  );
};

export default PrivateRoute;
