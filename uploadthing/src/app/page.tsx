import ImageUpload from "./components/image-upload";
import styles from "./page.module.css";

export default function Home() {
  return (
    <main className={styles.main}>
      <ImageUpload/>
    </main>
  );
}
