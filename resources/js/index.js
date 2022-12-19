import React from "react";
import ReactDOM from "react-dom/client";
import { PersistGate } from "redux-persist/lib/integration/react";
import { Provider as ReduxProvider } from "react-redux";
import { HelmetProvider } from "react-helmet-async";
import { store, persistor } from "./redux/store";
import TaskManager from "./pages/TaskManager/TaskManager";
import ThemeProvider from "./theme";
import RtlLayout from './components/RtlLayout';
import ThemeColorPresets from "./components/ThemeColorPresets";

// scroll bar
import 'simplebar/src/simplebar.css';

// lightbox
import 'react-image-lightbox/style.css';
// lazy image
import 'react-lazy-load-image-component/src/effects/blur.css';
import 'react-lazy-load-image-component/src/effects/opacity.css';
import 'react-lazy-load-image-component/src/effects/black-and-white.css';

export default function HelloReact() {
    return <h1>Hello React!</h1>;
}

if (document.getElementById("root")) {
    const root = ReactDOM.createRoot(document.getElementById("root"));

    root.render(
        <HelmetProvider>
            <ReduxProvider store={store}>
                <PersistGate loading={null} persistor={persistor}>
                    <ThemeProvider>
                        <ThemeColorPresets>
                            <TaskManager />
                        </ThemeColorPresets>
                    </ThemeProvider>
                </PersistGate>
            </ReduxProvider>
        </HelmetProvider>
    );
}
