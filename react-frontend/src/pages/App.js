import React from 'react';
import {
  BrowserRouter as Router,
  Switch,
  Route
} from 'react-router-dom';

import PrivateRoute from '../hocs/PrivateRoute';

// pages
import Login from './Auth/Login';
import AuctionList from './Auction/List';
import AuctionDetail from './Auction/Detail';
import Settings from './Setting/Settings';
import ErrorPage from './Error/ErrorPage';

export default function App() {
  return (
    <Router>
      <Switch>
        <PrivateRoute exact path="/" component={AuctionList} />
        <PrivateRoute exact path="/detail/:auctionId" component={AuctionDetail} />
        <PrivateRoute exact path="/settings" component={Settings} />
        <Route exact path="/login" component={Login}/>
        <Route exact path="/error/:status?" component={ErrorPage}/>
        <Route path="/" component={ErrorPage}/>
      </Switch>
    </Router>
  );
}
