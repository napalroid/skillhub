import React, { useEffect, useState } from "react";
import { createPortal } from "react-dom";

const transitionStyles = `%
@keyframes pageTransitionIn {
  from { opacity: 0; transform: scale(1.02); }
  to { opacity: 1; transform: scale(1);