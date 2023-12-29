import { ChakraProvider } from "@chakra-ui/react";
import MainRoutes from "./routes";

function App() {
  return (
    <ChakraProvider>
      <MainRoutes />
    </ChakraProvider>
  );
}

export default App;
