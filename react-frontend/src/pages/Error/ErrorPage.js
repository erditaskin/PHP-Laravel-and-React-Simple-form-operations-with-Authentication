import { Link, useParams } from 'react-router-dom';

export default function ErrorPage() {
  const { status } = useParams();

  if (status >= 500) {
    return <Layout>
      <h1>Server Error!</h1>
      <hr />
      <p>Something went wrong!</p>
    </Layout>;
  }

  return <Layout>
    <h1>404 Error!</h1>
    <hr />
    <p>Page not found!</p>
  </Layout>;
}

function Layout({ children }) {
  return (
    <div className="container mt-5">
      <div className="row justify-content-center">
        <div className="col-4">
          {children}
          <Link to="/" className="btn btn-secondary">Go Back</Link>
        </div>
      </div>
    </div>
  );
}