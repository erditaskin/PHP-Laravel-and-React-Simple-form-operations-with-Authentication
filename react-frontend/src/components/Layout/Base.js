import Navbar from './Navbar';

export default function Layout(props) {
  return (
    <div>
      <Navbar />
      <main className="container pt-3">
        {props.children}
      </main>
    </div>
  );
}