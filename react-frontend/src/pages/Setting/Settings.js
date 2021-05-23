import { useEffect, useState } from 'react';
import { useFormik } from 'formik';
import * as Yup from 'yup';

import { apiSetting, apiSettingUpdate } from '../../api/setting';

export default function Settings() {
  const [loading, setLoading] = useState(true);
  const [maxAmount, setMaxAmount] = useState(1);

  useEffect(() => {
    apiSetting()
    .then((res) => {
      if (res.data !== null) {
        setMaxAmount(res.data.max_amount);
      }
      setLoading(false);
    })
    .catch(() => {});
  }, [maxAmount]);

  const formik = useFormik({
    enableReinitialize: true,
    initialValues: {
      maxAmount: maxAmount,
    },
    validationSchema: Yup.object({
      maxAmount: Yup.number().required('Required'),
    }),
    onSubmit: (values) => {
      apiSettingUpdate(values)
        .then(res => {
          alert('The maximum bid amount has been updated!');
          setMaxAmount(res.data.max_amount);
        })
        .catch(e => {
          console.log(e)
        });
    },
  });

  if (loading) {
    return <div>Loading</div>;
  }

  return (
    <div className="white-wrapper">
      <div className="row justify-content-center">
        <h1>User Settings</h1>
        <form onSubmit={formik.handleSubmit}>
          <div className="mb-3">
            <label htmlFor="maxAmount" className="form-label">Maximum Bid Amount</label>
            <input
              id="maxAmount"
              name="maxAmount"
              type="number"
              className="form-control"
              onChange={formik.handleChange}
              onBlur={formik.handleBlur}
              value={formik.values.maxAmount}
              required
            />
            {formik.touched.maxAmount && formik.errors.maxAmount ? (
              <div>{formik.errors.maxAmount}</div>
            ) : null}
          </div>
          <button type="submit" className="btn btn-primary">Save</button>
        </form>
        <div className="alert alert-info mt-3" role="alert">
          <ul>
            <li>Maximum amount will split between auto-bids.</li>
            <li>Auto bidding will stop automatically when you got out of funds.</li>
          </ul>
        </div>
      </div>
    </div>
  );
}