import { Redirect } from 'react-router-dom';
import { useFormik } from 'formik';
import * as Yup from 'yup';

import { apiLogin, setUserToStorage, isLogged } from '../../api/login';

export default function Login() {
  const formik = useFormik({
    initialValues: {
      username: '',
      password: '',
    },
    validationSchema: Yup.object({
      username: Yup.string().required('Required'),
      password: Yup.string()
        .min(3, 'Must be 3 characters or more')
        .required('Required'),
    }),
    onSubmit: (values) => {
      apiLogin(values)
        .then(res => {
          console.log(res)
          if (res.code === 200) {
            setUserToStorage(res.user_id, res.username);
            window.location.href = '/';
          } else {
            alert("Please check your login information!")
          }
        })
        .catch(e => {
          alert("Please check your login information!")
        });
    },
  });

  if (isLogged) {
    return <Redirect to="/" />;
  }

  return (
    <div className="container">
      <div className="row wrapper">
        <div className="form-content">
          <h1 className="mb-4">Login</h1>
          <form onSubmit={formik.handleSubmit}>
            <div className="mb-3">
              <label htmlFor="username" className="form-label">Username</label>
              <input
                id="username"
                name="username"
                type="text"
                className="form-control"
                onChange={formik.handleChange}
                onBlur={formik.handleBlur}
                value={formik.values.username}
                required
              />
              {formik.touched.username && formik.errors.username ? (
                <div className="formError">{formik.errors.username}</div>
              ) : null}
            </div>
            <div className="mb-3">
              <label htmlFor="password" className="form-label">Password</label>
              <input
                id="password"
                name="password"
                type="password"
                className="form-control"
                onChange={formik.handleChange}
                onBlur={formik.handleBlur}
                value={formik.values.password}
                required
              />
              {formik.touched.password && formik.errors.password ? (
                <div className="form-error">{formik.errors.password}</div>
              ) : null}
            </div>
            <button type="submit" className="btn btn-primary">Login</button>
          </form>
        </div>
      </div>
    </div>
  );
}
