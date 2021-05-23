import React, { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';

import { apiAuctionDetail, apiMakeBid, apiActivateAutoBid } from '../../api/auction';

export default function Detail() {
  const { auctionId } = useParams();

  const [loading, setLoading] = useState(true);
  const [auction, setAuction] = useState(null);
  const [setting, setSetting] = useState(null);
  const [countdown, setCountdown] = useState('');
  const [amount, setAmount] = useState('');
  const [autoBid, setAutoBid] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    apiAuctionDetail(auctionId)
      .then(res => {
        setError(null);
        setAuction(res.data);
        setSetting(res.setting);
        setLoading(false);
        setCountdown(timer(res.data.close_datetime));

        if (res.data.auto_bid) {
          setAutoBid(true);
        }

        if (res.code === 406 || res.code === 400) {
          setError(res.message);
        }
      })
      .catch((e) => setError('Something went wrong!'));
  }, [auctionId]);

  useEffect(() => {
    const interval = setInterval(() => {
      setCountdown(timer(auction.close_datetime));
    }, 1000);

    return () => clearInterval(interval);
  });

  const makeBid = (e) => {
    e.preventDefault();

    apiMakeBid(auctionId, { price: amount })
      .then(res => {
        setError(null);
        setAuction(res.data);

        if (res.code === 200) {
          setAmount('');
        } else if (res.code === 406 || res.code === 400) {
          setError(res.message)
        }
      })
      .catch(() => setError('You cannot make a bid for it!'));
  };

  const activateAutoBid = (e) => {
    apiActivateAutoBid(auctionId, { status: !autoBid })
      .then(() => setAutoBid(!autoBid))
      .catch(() => setError('You cannot activate auto-bidding!'));
  };

  if (loading) {
    return <div>Loading...</div>;
  }

  if (typeof auction !== 'object') {
    return <div className="alert alert-dark" role="alert">Something went wrong. Please refresh the page!</div>;
  }

  return (
    <div className="white-wrapper">
      <div className="row mt-3">
        <Error error={error} />
        <div className="col-sm-4 mb-3">
          <div className="card"><img src={auction.full_image} alt={auction.name} className="img-fluid" /></div>
        </div>
        <div className="col-sm-8">
          <h1>{auction.name}</h1>
          <p className="text-muted">{auction.description}</p>
          <h3>{countdown ? countdown : auction.close_datetime}</h3>
          <p className="text-muted">{(new Date(auction.close_datetime).toLocaleString())}</p>
          <h2>${auction.last_price}</h2>
          <BidNow makeBid={makeBid} setAmount={setAmount} amount={amount} lastPrice={auction.last_price} />
          <AutoBidding autoBid={autoBid} activateAutoBid={activateAutoBid} setting={setting} />
          <BidHistory bids={auction.bids} />
        </div>
      </div>
    </div>   
  );
}

const Error = ({ error }) => error && (
  <div className="col-12">
    <div className="alert alert-dark" role="alert">
      {error}
    </div>
  </div>
);

const BidNow = ({ makeBid, setAmount, amount, lastPrice }) => (
  <form onSubmit={(e) => makeBid(e)}>
    <div className="row">
      <div className="col-sm-6">
        <div className="input-group mb-3">
          <input type="number"
            className={"form-control " + (amount && amount <= lastPrice && ' is-invalid ')}
            value={amount} placeholder="Amount" 
            aria-label="Amount" aria-describedby="bid" required
            onChange={(e) => { setAmount(e.target.value) }} />
          <button className="btn btn-success" type="submit" id="bid" disabled={amount <= lastPrice ? true : false}>Submit Your Bid</button>
          {amount && amount <= lastPrice && <div className="invalid-feedback">You cannot make a bid lower than the last price!</div>}
        </div>
      </div>
    </div>
  </form>
);

const AutoBidding = ({ activateAutoBid, autoBid, setting }) => (
  <React.Fragment>
    <div className="form-check">
      <input className="form-check-input"
        type="checkbox" id="autoBid" value={autoBid}
        onChange={() => activateAutoBid()} checked={autoBid ? true : false} />
      <label className="form-check-label" htmlFor="autoBid">
        Activate Auto-Bidding
    </label>
      {setting && setting.max_amount > 0 && <div className="text-muted"> Your max bidding setting: ${setting.max_amount}</div>}
    </div>
    {autoBid && setting && setting.max_amount < 1 && (
      <div className="text-muted">
        You need to set the maximum amount for Auto-bidding. <br />
        <Link to="/settings">Set the Max Amount</Link>
      </div>
    )}
  </React.Fragment>
);

const BidHistory = ({ bids }) => bids.length > 0 && (
  <div className="mt-5">
    <h5>Bid History</h5>
    <table className="table">
      <thead>
        <tr>
          <th scope="col">Bidder</th>
          <th scope="col">Amount</th>
          <th scope="col">Bid Time</th>
        </tr>
      </thead>
      <tbody>
        {bids.map((bid, index) => (
          <tr key={index}>
            <th scope="row">{bid.user.username}</th>
            <td>${bid.price}</td>
            <td>{new Date(bid.created_at).toLocaleString()}</td>
          </tr>
        ))}
      </tbody>
    </table>
  </div>
);

/**
 * Helper function
 * 
 * @param {*} date 
 * @returns 
 */
function timer(date) {
  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = new Date(date) - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  if (distance < 0) {
    return "EXPIRED";
  }

  return days + " Days " + hours + " Hours " + minutes + " Minutes " + seconds + " Seconds ";
}